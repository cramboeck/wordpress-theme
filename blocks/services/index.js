/**
 * Services Block
 */

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, TextControl, TextareaControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType('ramboeck/services', {
	edit: ({ attributes, setAttributes }) => {
		const { columns, services } = attributes;

		const blockProps = useBlockProps({
			className: 'services',
			style: { '--services-columns': columns },
		});

		const addService = () => {
			setAttributes({
				services: [
					...services,
					{ icon: 'check', title: '', description: '', link: '' },
				],
			});
		};

		const updateService = (index, field, value) => {
			const updated = [...services];
			updated[index] = { ...updated[index], [field]: value };
			setAttributes({ services: updated });
		};

		const removeService = (index) => {
			const updated = services.filter((_, i) => i !== index);
			setAttributes({ services: updated });
		};

		return (
			<>
				<InspectorControls>
					<PanelBody title={__('Layout', 'ramboeck')}>
						<RangeControl
							label={__('Spalten', 'ramboeck')}
							value={columns}
							onChange={(value) => setAttributes({ columns: value })}
							min={1}
							max={4}
						/>
					</PanelBody>

					<PanelBody title={__('Leistungen', 'ramboeck')} initialOpen={true}>
						{services.map((service, index) => (
							<div key={index} style={{ marginBottom: '24px', padding: '12px', background: '#f0f0f0', borderRadius: '4px' }}>
								<TextControl
									label={__('Icon', 'ramboeck')}
									value={service.icon}
									onChange={(value) => updateService(index, 'icon', value)}
									help={__('z.B. check, star, phone', 'ramboeck')}
								/>
								<TextControl
									label={__('Titel', 'ramboeck')}
									value={service.title}
									onChange={(value) => updateService(index, 'title', value)}
								/>
								<TextareaControl
									label={__('Beschreibung', 'ramboeck')}
									value={service.description}
									onChange={(value) => updateService(index, 'description', value)}
								/>
								<TextControl
									label={__('Link', 'ramboeck')}
									value={service.link}
									onChange={(value) => updateService(index, 'link', value)}
								/>
								<Button isDestructive onClick={() => removeService(index)}>
									{__('Entfernen', 'ramboeck')}
								</Button>
							</div>
						))}
						<Button variant="primary" onClick={addService}>
							{__('Leistung hinzufügen', 'ramboeck')}
						</Button>
					</PanelBody>
				</InspectorControls>

				<section {...blockProps}>
					<div className="services__grid">
						{services.length === 0 ? (
							<p style={{ gridColumn: '1 / -1', textAlign: 'center', color: '#666' }}>
								{__('Füge Leistungen über die Seitenleiste hinzu.', 'ramboeck')}
							</p>
						) : (
							services.map((service, index) => (
								<article key={index} className="service-card">
									<div className="service-card__icon">
										<span>{service.icon || '✓'}</span>
									</div>
									<h3 className="service-card__title">
										{service.title || __('Titel', 'ramboeck')}
									</h3>
									<p className="service-card__description">
										{service.description || __('Beschreibung', 'ramboeck')}
									</p>
								</article>
							))
						)}
					</div>
				</section>
			</>
		);
	},

	save: () => null,
});
