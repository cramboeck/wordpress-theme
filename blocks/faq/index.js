/**
 * FAQ Block
 */

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType('ramboeck/faq', {
	edit: ({ attributes, setAttributes }) => {
		const { items } = attributes;

		const blockProps = useBlockProps({ className: 'faq' });

		const addItem = () => {
			setAttributes({
				items: [...items, { question: '', answer: '' }],
			});
		};

		const updateItem = (index, field, value) => {
			const updated = [...items];
			updated[index] = { ...updated[index], [field]: value };
			setAttributes({ items: updated });
		};

		const removeItem = (index) => {
			setAttributes({ items: items.filter((_, i) => i !== index) });
		};

		return (
			<>
				<InspectorControls>
					<PanelBody title={__('FAQ Einträge', 'ramboeck')} initialOpen={true}>
						{items.map((item, index) => (
							<div key={index} style={{ marginBottom: '24px', padding: '12px', background: '#f0f0f0', borderRadius: '4px' }}>
								<TextControl
									label={__('Frage', 'ramboeck')}
									value={item.question}
									onChange={(value) => updateItem(index, 'question', value)}
								/>
								<TextareaControl
									label={__('Antwort', 'ramboeck')}
									value={item.answer}
									onChange={(value) => updateItem(index, 'answer', value)}
								/>
								<Button isDestructive onClick={() => removeItem(index)}>
									{__('Entfernen', 'ramboeck')}
								</Button>
							</div>
						))}
						<Button variant="primary" onClick={addItem}>
							{__('Frage hinzufügen', 'ramboeck')}
						</Button>
					</PanelBody>
				</InspectorControls>

				<section {...blockProps}>
					<div className="faq__container" style={{ maxWidth: '800px', margin: '0 auto', padding: '48px 24px' }}>
						{items.length === 0 ? (
							<p style={{ textAlign: 'center', color: '#666' }}>
								{__('Füge FAQ-Einträge über die Seitenleiste hinzu.', 'ramboeck')}
							</p>
						) : (
							items.map((item, index) => (
								<div key={index} className="faq__item" style={{ borderBottom: '1px solid #e2e8f0', padding: '16px 0' }}>
									<div className="faq__question" style={{ display: 'flex', justifyContent: 'space-between', fontWeight: '600' }}>
										<span>{item.question || __('Frage...', 'ramboeck')}</span>
										<span>▼</span>
									</div>
									<div className="faq__answer" style={{ marginTop: '12px', color: '#666' }}>
										{item.answer || __('Antwort...', 'ramboeck')}
									</div>
								</div>
							))
						)}
					</div>
				</section>
			</>
		);
	},

	save: () => null,
});
