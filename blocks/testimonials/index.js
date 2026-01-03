/**
 * Testimonials Block
 */

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls, MediaUpload } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType('ramboeck/testimonials', {
	edit: ({ attributes, setAttributes }) => {
		const { testimonials } = attributes;

		const blockProps = useBlockProps({ className: 'testimonials' });

		const addTestimonial = () => {
			setAttributes({
				testimonials: [
					...testimonials,
					{ quote: '', name: '', role: '', avatar: '' },
				],
			});
		};

		const updateTestimonial = (index, field, value) => {
			const updated = [...testimonials];
			updated[index] = { ...updated[index], [field]: value };
			setAttributes({ testimonials: updated });
		};

		const removeTestimonial = (index) => {
			setAttributes({ testimonials: testimonials.filter((_, i) => i !== index) });
		};

		return (
			<>
				<InspectorControls>
					<PanelBody title={__('Testimonials', 'ramboeck')} initialOpen={true}>
						{testimonials.map((item, index) => (
							<div key={index} style={{ marginBottom: '24px', padding: '12px', background: '#f0f0f0', borderRadius: '4px' }}>
								<TextareaControl
									label={__('Zitat', 'ramboeck')}
									value={item.quote}
									onChange={(value) => updateTestimonial(index, 'quote', value)}
								/>
								<TextControl
									label={__('Name', 'ramboeck')}
									value={item.name}
									onChange={(value) => updateTestimonial(index, 'name', value)}
								/>
								<TextControl
									label={__('Position/Firma', 'ramboeck')}
									value={item.role}
									onChange={(value) => updateTestimonial(index, 'role', value)}
								/>
								<MediaUpload
									onSelect={(media) => updateTestimonial(index, 'avatar', media.url)}
									allowedTypes={['image']}
									render={({ open }) => (
										<Button onClick={open} variant="secondary" style={{ marginBottom: '8px' }}>
											{item.avatar ? __('Avatar ändern', 'ramboeck') : __('Avatar wählen', 'ramboeck')}
										</Button>
									)}
								/>
								<Button isDestructive onClick={() => removeTestimonial(index)}>
									{__('Entfernen', 'ramboeck')}
								</Button>
							</div>
						))}
						<Button variant="primary" onClick={addTestimonial}>
							{__('Testimonial hinzufügen', 'ramboeck')}
						</Button>
					</PanelBody>
				</InspectorControls>

				<section {...blockProps}>
					<div className="testimonials__container">
						<div className="testimonials__grid" style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: '24px' }}>
							{testimonials.length === 0 ? (
								<p style={{ gridColumn: '1 / -1', textAlign: 'center' }}>
									{__('Füge Testimonials über die Seitenleiste hinzu.', 'ramboeck')}
								</p>
							) : (
								testimonials.map((item, index) => (
									<article key={index} className="testimonial-card" style={{ padding: '24px', background: '#fff', borderRadius: '8px' }}>
										<div style={{ color: '#fbbf24', marginBottom: '12px' }}>★★★★★</div>
										<blockquote style={{ margin: '0 0 16px', fontStyle: 'italic' }}>
											"{item.quote || __('Zitat...', 'ramboeck')}"
										</blockquote>
										<div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
											{item.avatar && <img src={item.avatar} alt="" style={{ width: '40px', height: '40px', borderRadius: '50%' }} />}
											<div>
												<strong>{item.name || __('Name', 'ramboeck')}</strong>
												<br />
												<small style={{ color: '#666' }}>{item.role || __('Position', 'ramboeck')}</small>
											</div>
										</div>
									</article>
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
