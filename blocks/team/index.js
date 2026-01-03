/**
 * Team Block
 */

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls, MediaUpload } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl, RangeControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType('ramboeck/team', {
	edit: ({ attributes, setAttributes }) => {
		const { members, columns } = attributes;
		const blockProps = useBlockProps({ className: 'team', style: { '--team-columns': columns } });

		const addMember = () => {
			setAttributes({
				members: [...members, { name: '', role: '', bio: '', image: '', email: '', linkedin: '' }],
			});
		};

		const updateMember = (index, field, value) => {
			const updated = [...members];
			updated[index] = { ...updated[index], [field]: value };
			setAttributes({ members: updated });
		};

		const removeMember = (index) => {
			setAttributes({ members: members.filter((_, i) => i !== index) });
		};

		return (
			<>
				<InspectorControls>
					<PanelBody title={__('Layout', 'ramboeck')}>
						<RangeControl label={__('Spalten', 'ramboeck')} value={columns} onChange={(v) => setAttributes({ columns: v })} min={2} max={6} />
					</PanelBody>
					<PanelBody title={__('Teammitglieder', 'ramboeck')} initialOpen={true}>
						{members.map((member, index) => (
							<div key={index} style={{ marginBottom: '24px', padding: '12px', background: '#f0f0f0', borderRadius: '4px' }}>
								<MediaUpload
									onSelect={(media) => updateMember(index, 'image', media.url)}
									allowedTypes={['image']}
									render={({ open }) => (
										<Button onClick={open} variant="secondary" style={{ marginBottom: '12px' }}>
											{member.image ? __('Bild ändern', 'ramboeck') : __('Bild wählen', 'ramboeck')}
										</Button>
									)}
								/>
								{member.image && <img src={member.image} alt="" style={{ width: '100%', marginBottom: '12px', borderRadius: '8px' }} />}
								<TextControl label={__('Name', 'ramboeck')} value={member.name} onChange={(v) => updateMember(index, 'name', v)} />
								<TextControl label={__('Position', 'ramboeck')} value={member.role} onChange={(v) => updateMember(index, 'role', v)} />
								<TextareaControl label={__('Bio', 'ramboeck')} value={member.bio} onChange={(v) => updateMember(index, 'bio', v)} />
								<TextControl label={__('E-Mail', 'ramboeck')} value={member.email} onChange={(v) => updateMember(index, 'email', v)} />
								<TextControl label={__('LinkedIn URL', 'ramboeck')} value={member.linkedin} onChange={(v) => updateMember(index, 'linkedin', v)} />
								<Button isDestructive onClick={() => removeMember(index)}>{__('Entfernen', 'ramboeck')}</Button>
							</div>
						))}
						<Button variant="primary" onClick={addMember}>{__('Mitglied hinzufügen', 'ramboeck')}</Button>
					</PanelBody>
				</InspectorControls>

				<section {...blockProps}>
					<div className="team__container">
						<div className="team__grid" style={{ display: 'grid', gridTemplateColumns: `repeat(${columns}, 1fr)`, gap: '24px' }}>
							{members.length === 0 ? (
								<p style={{ textAlign: 'center', gridColumn: '1 / -1' }}>{__('Füge Teammitglieder über die Seitenleiste hinzu.', 'ramboeck')}</p>
							) : (
								members.map((member, index) => (
									<div key={index} className="team-card" style={{ textAlign: 'center' }}>
										{member.image && <img src={member.image} alt="" style={{ width: '100%', aspectRatio: '1', objectFit: 'cover', borderRadius: '12px', marginBottom: '16px' }} />}
										<h3 style={{ margin: '0 0 4px', fontSize: '1.125rem' }}>{member.name || 'Name'}</h3>
										<p style={{ color: '#3b82f6', fontSize: '0.875rem', margin: '0 0 12px' }}>{member.role || 'Position'}</p>
										<p style={{ color: '#666', fontSize: '0.875rem' }}>{member.bio}</p>
									</div>
								))
							)}
						</div>
					</div>
				</section>
			</>
		);
	},
	save: () => null,
});
