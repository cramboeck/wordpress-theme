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
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
											{service.icon === 'monitor' && <><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></>}
											{service.icon === 'server' && <><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></>}
											{service.icon === 'cloud' && <path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"></path>}
											{service.icon === 'shield' && <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>}
											{service.icon === 'code' && <><polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline></>}
											{service.icon === 'check' && <polyline points="20 6 9 17 4 12"></polyline>}
											{service.icon === 'star' && <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>}
											{service.icon === 'phone' && <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"></path>}
											{service.icon === 'database' && <><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></>}
											{service.icon === 'cpu' && <><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect></>}
											{!['monitor','server','cloud','shield','code','check','star','phone','database','cpu'].includes(service.icon) && <polyline points="20 6 9 17 4 12"></polyline>}
										</svg>
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
