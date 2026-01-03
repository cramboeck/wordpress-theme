/**
 * Pricing Block
 */

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl, ToggleControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType('ramboeck/pricing', {
	edit: ({ attributes, setAttributes }) => {
		const { plans } = attributes;
		const blockProps = useBlockProps({ className: 'pricing' });

		const addPlan = () => {
			setAttributes({
				plans: [...plans, { name: '', price: '', period: 'Monat', description: '', features: [], buttonText: 'Auswählen', buttonUrl: '', featured: false }],
			});
		};

		const updatePlan = (index, field, value) => {
			const updated = [...plans];
			updated[index] = { ...updated[index], [field]: value };
			setAttributes({ plans: updated });
		};

		const removePlan = (index) => {
			setAttributes({ plans: plans.filter((_, i) => i !== index) });
		};

		const updateFeatures = (index, value) => {
			const features = value.split('\n').filter(f => f.trim());
			updatePlan(index, 'features', features);
		};

		return (
			<>
				<InspectorControls>
					<PanelBody title={__('Preispläne', 'ramboeck')} initialOpen={true}>
						{plans.map((plan, index) => (
							<div key={index} style={{ marginBottom: '24px', padding: '12px', background: '#f0f0f0', borderRadius: '4px' }}>
								<TextControl label={__('Name', 'ramboeck')} value={plan.name} onChange={(v) => updatePlan(index, 'name', v)} />
								<TextControl label={__('Preis', 'ramboeck')} value={plan.price} onChange={(v) => updatePlan(index, 'price', v)} />
								<TextControl label={__('Zeitraum', 'ramboeck')} value={plan.period} onChange={(v) => updatePlan(index, 'period', v)} />
								<TextareaControl label={__('Beschreibung', 'ramboeck')} value={plan.description} onChange={(v) => updatePlan(index, 'description', v)} />
								<TextareaControl label={__('Features (eine pro Zeile)', 'ramboeck')} value={(plan.features || []).join('\n')} onChange={(v) => updateFeatures(index, v)} />
								<TextControl label={__('Button Text', 'ramboeck')} value={plan.buttonText} onChange={(v) => updatePlan(index, 'buttonText', v)} />
								<TextControl label={__('Button URL', 'ramboeck')} value={plan.buttonUrl} onChange={(v) => updatePlan(index, 'buttonUrl', v)} />
								<ToggleControl label={__('Hervorgehoben', 'ramboeck')} checked={plan.featured} onChange={(v) => updatePlan(index, 'featured', v)} />
								<Button isDestructive onClick={() => removePlan(index)}>{__('Entfernen', 'ramboeck')}</Button>
							</div>
						))}
						<Button variant="primary" onClick={addPlan}>{__('Plan hinzufügen', 'ramboeck')}</Button>
					</PanelBody>
				</InspectorControls>

				<section {...blockProps}>
					<div className="pricing__container">
						<div className="pricing__grid" style={{ display: 'grid', gridTemplateColumns: `repeat(${Math.min(plans.length || 1, 3)}, 1fr)`, gap: '24px' }}>
							{plans.length === 0 ? (
								<p style={{ textAlign: 'center', gridColumn: '1 / -1' }}>{__('Füge Preispläne über die Seitenleiste hinzu.', 'ramboeck')}</p>
							) : (
								plans.map((plan, index) => (
									<div key={index} className={`pricing-card ${plan.featured ? 'pricing-card--featured' : ''}`} style={{ padding: '32px', background: '#fff', border: plan.featured ? '2px solid #3b82f6' : '1px solid #e2e8f0', borderRadius: '12px', textAlign: 'center' }}>
										{plan.featured && <div style={{ background: '#3b82f6', color: '#fff', padding: '4px 16px', borderRadius: '999px', fontSize: '12px', marginBottom: '16px', display: 'inline-block' }}>Beliebt</div>}
										<h3 style={{ margin: '0 0 8px' }}>{plan.name || 'Plan'}</h3>
										<div style={{ fontSize: '2rem', fontWeight: '700' }}>{plan.price || '€0'}<span style={{ fontSize: '0.875rem', color: '#666' }}>/{plan.period}</span></div>
										<p style={{ color: '#666', margin: '16px 0' }}>{plan.description}</p>
										<ul style={{ textAlign: 'left', listStyle: 'none', padding: 0 }}>
											{(plan.features || []).map((f, i) => <li key={i} style={{ padding: '8px 0', borderBottom: '1px solid #eee' }}>✓ {f}</li>)}
										</ul>
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
