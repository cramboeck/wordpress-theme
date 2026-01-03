/**
 * Features Block
 */

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl, RangeControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType('ramboeck/features', {
	edit: ({ attributes, setAttributes }) => {
		const { features, columns } = attributes;
		const blockProps = useBlockProps({ className: 'features', style: { '--features-columns': columns } });

		const addFeature = () => {
			setAttributes({
				features: [...features, { icon: 'check', title: '', description: '' }],
			});
		};

		const updateFeature = (index, field, value) => {
			const updated = [...features];
			updated[index] = { ...updated[index], [field]: value };
			setAttributes({ features: updated });
		};

		const removeFeature = (index) => {
			setAttributes({ features: features.filter((_, i) => i !== index) });
		};

		return (
			<>
				<InspectorControls>
					<PanelBody title={__('Layout', 'ramboeck')}>
						<RangeControl label={__('Spalten', 'ramboeck')} value={columns} onChange={(v) => setAttributes({ columns: v })} min={1} max={4} />
					</PanelBody>
					<PanelBody title={__('Features', 'ramboeck')} initialOpen={true}>
						{features.map((feature, index) => (
							<div key={index} style={{ marginBottom: '24px', padding: '12px', background: '#f0f0f0', borderRadius: '4px' }}>
								<TextControl label={__('Icon', 'ramboeck')} value={feature.icon} onChange={(v) => updateFeature(index, 'icon', v)} help="check, star, phone, mail, etc." />
								<TextControl label={__('Titel', 'ramboeck')} value={feature.title} onChange={(v) => updateFeature(index, 'title', v)} />
								<TextareaControl label={__('Beschreibung', 'ramboeck')} value={feature.description} onChange={(v) => updateFeature(index, 'description', v)} />
								<Button isDestructive onClick={() => removeFeature(index)}>{__('Entfernen', 'ramboeck')}</Button>
							</div>
						))}
						<Button variant="primary" onClick={addFeature}>{__('Feature hinzufügen', 'ramboeck')}</Button>
					</PanelBody>
				</InspectorControls>

				<section {...blockProps}>
					<div className="features__container">
						<div className="features__grid" style={{ display: 'grid', gridTemplateColumns: `repeat(${columns}, 1fr)`, gap: '32px' }}>
							{features.length === 0 ? (
								<p style={{ textAlign: 'center', gridColumn: '1 / -1' }}>{__('Füge Features über die Seitenleiste hinzu.', 'ramboeck')}</p>
							) : (
								features.map((feature, index) => (
									<div key={index} className="feature-item" style={{ display: 'flex', gap: '16px' }}>
										<div style={{ width: '48px', height: '48px', background: '#3b82f6', borderRadius: '8px', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#fff' }}>✓</div>
										<div>
											<h3 style={{ margin: '0 0 8px', fontSize: '1.125rem' }}>{feature.title || 'Titel'}</h3>
											<p style={{ margin: 0, color: '#666' }}>{feature.description || 'Beschreibung'}</p>
										</div>
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
