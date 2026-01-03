/**
 * AI Page Optimizer - Gutenberg Sidebar Plugin
 *
 * @package Ramboeck
 */

import { registerPlugin } from '@wordpress/plugins';
import { PluginSidebar, PluginSidebarMoreMenuItem } from '@wordpress/edit-post';
import { Fragment, useState, useEffect } from '@wordpress/element';
import { useSelect, useDispatch } from '@wordpress/data';
import {
	PanelBody,
	Button,
	Spinner,
	Notice,
	SelectControl,
	Card,
	CardBody,
	CardHeader,
	__experimentalText as Text,
	Flex,
	FlexItem,
	Icon,
	Modal,
	TextareaControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { starFilled, check, plus, pencil } from '@wordpress/icons';

// Use starFilled as sparkles alternative
const sparkles = starFilled;

const { ajaxUrl, nonce, isConfigured, settingsUrl, strings } = window.ramboeckOptimizer || {};

/**
 * Score Badge Component
 */
const ScoreBadge = ({ score }) => {
	let color = '#ef4444'; // red
	if (score >= 80) color = '#22c55e'; // green
	else if (score >= 60) color = '#eab308'; // yellow
	else if (score >= 40) color = '#f97316'; // orange

	return (
		<div style={{
			display: 'inline-flex',
			alignItems: 'center',
			justifyContent: 'center',
			width: '60px',
			height: '60px',
			borderRadius: '50%',
			backgroundColor: color,
			color: 'white',
			fontSize: '1.5rem',
			fontWeight: 'bold',
		}}>
			{score}
		</div>
	);
};

/**
 * Priority Badge Component
 */
const PriorityBadge = ({ priority }) => {
	const colors = {
		high: { bg: '#fef2f2', color: '#dc2626', label: 'Hoch' },
		medium: { bg: '#fffbeb', color: '#d97706', label: 'Mittel' },
		low: { bg: '#f0fdf4', color: '#16a34a', label: 'Niedrig' },
	};
	const style = colors[priority] || colors.medium;

	return (
		<span style={{
			display: 'inline-block',
			padding: '2px 8px',
			borderRadius: '4px',
			backgroundColor: style.bg,
			color: style.color,
			fontSize: '11px',
			fontWeight: '600',
		}}>
			{style.label}
		</span>
	);
};

/**
 * Main Page Optimizer Component
 */
const PageOptimizer = () => {
	const [isAnalyzing, setIsAnalyzing] = useState(false);
	const [isImproving, setIsImproving] = useState(false);
	const [isGenerating, setIsGenerating] = useState(false);
	const [analysis, setAnalysis] = useState(null);
	const [error, setError] = useState(null);
	const [selectedSection, setSelectedSection] = useState('');
	const [showGenerateModal, setShowGenerateModal] = useState(false);
	const [generatedBlocks, setGeneratedBlocks] = useState(null);

	const { editPost } = useDispatch('core/editor');
	const { insertBlocks } = useDispatch('core/block-editor');

	const { content, title, postType } = useSelect((select) => {
		const editor = select('core/editor');
		return {
			content: editor.getEditedPostContent(),
			title: editor.getEditedPostAttribute('title'),
			postType: editor.getCurrentPostType(),
		};
	});

	// Check if API is configured
	if (!isConfigured) {
		return (
			<PanelBody>
				<Notice status="warning" isDismissible={false}>
					{strings.notConfigured}
				</Notice>
				<p style={{ marginTop: '10px', fontSize: '12px', color: '#666' }}>
					{strings.configureHint || 'Bitte API-Key in den AI-Einstellungen hinterlegen.'}
				</p>
				{settingsUrl && (
					<Button
						variant="secondary"
						href={settingsUrl}
						style={{ marginTop: '10px' }}
					>
						Einstellungen öffnen
					</Button>
				)}
			</PanelBody>
		);
	}

	/**
	 * Analyze page content
	 */
	const analyzePage = async () => {
		if (!content || content.trim() === '') {
			setError(strings.noContent);
			return;
		}

		setIsAnalyzing(true);
		setError(null);
		setAnalysis(null);

		try {
			const formData = new FormData();
			formData.append('action', 'ramboeck_analyze_page');
			formData.append('nonce', nonce);
			formData.append('content', content);
			formData.append('title', title);
			formData.append('pageType', postType);

			const response = await fetch(ajaxUrl, {
				method: 'POST',
				body: formData,
			});

			const data = await response.json();

			if (data.success) {
				setAnalysis(data.data);
			} else {
				setError(data.data?.message || strings.error);
			}
		} catch (err) {
			setError(strings.error);
		} finally {
			setIsAnalyzing(false);
		}
	};

	/**
	 * Generate a new section
	 */
	const generateSection = async () => {
		if (!selectedSection) return;

		setIsGenerating(true);
		setError(null);

		try {
			const formData = new FormData();
			formData.append('action', 'ramboeck_generate_section');
			formData.append('nonce', nonce);
			formData.append('sectionType', selectedSection);
			formData.append('context', `Seitentitel: ${title}`);
			formData.append('businessInfo', 'Ramböck IT - IT-Dienstleister in Passau und Niederbayern');

			const response = await fetch(ajaxUrl, {
				method: 'POST',
				body: formData,
			});

			const data = await response.json();

			if (data.success) {
				setGeneratedBlocks(data.data.blocks);
				setShowGenerateModal(true);
			} else {
				setError(data.data?.message || strings.error);
			}
		} catch (err) {
			setError(strings.error);
		} finally {
			setIsGenerating(false);
		}
	};

	/**
	 * Insert generated blocks
	 */
	const applyGeneratedBlocks = () => {
		if (generatedBlocks) {
			// Parse and insert blocks
			const { parse } = wp.blocks;
			const blocks = parse(generatedBlocks);
			insertBlocks(blocks);
			setShowGenerateModal(false);
			setGeneratedBlocks(null);
		}
	};

	const sectionOptions = [
		{ label: strings.selectSection, value: '' },
		{ label: 'Hero Section', value: 'hero' },
		{ label: 'Services/Leistungen', value: 'services' },
		{ label: 'Features/Vorteile', value: 'features' },
		{ label: 'Testimonials', value: 'testimonials' },
		{ label: 'FAQ', value: 'faq' },
		{ label: 'Call-to-Action', value: 'cta' },
		{ label: 'Statistiken', value: 'stats' },
		{ label: 'Kontaktformular', value: 'contact' },
	];

	return (
		<Fragment>
			{/* Analysis Panel */}
			<PanelBody title={strings.title} initialOpen={true}>
				{error && (
					<Notice status="error" isDismissible onDismiss={() => setError(null)}>
						{error}
					</Notice>
				)}

				<Button
					variant="primary"
					onClick={analyzePage}
					disabled={isAnalyzing}
					style={{ width: '100%', marginBottom: '15px', justifyContent: 'center' }}
				>
					{isAnalyzing ? (
						<Fragment>
							<Spinner /> {strings.analyzing}
						</Fragment>
					) : (
						<Fragment>
							<Icon icon={sparkles} style={{ marginRight: '8px' }} />
							{strings.analyze}
						</Fragment>
					)}
				</Button>

				{/* Analysis Results */}
				{analysis && (
					<div className="ramboeck-analysis-results">
						{/* SEO Score */}
						<Card style={{ marginBottom: '15px' }}>
							<CardBody>
								<Flex align="center" gap={4}>
									<FlexItem>
										<ScoreBadge score={analysis.seoScore} />
									</FlexItem>
									<FlexItem>
										<Text weight="600">{strings.seoScore}</Text>
										<Text variant="muted" size="12px">
											{analysis.summary}
										</Text>
									</FlexItem>
								</Flex>
							</CardBody>
						</Card>

						{/* Strengths */}
						{analysis.strengths?.length > 0 && (
							<div style={{ marginBottom: '15px' }}>
								<Text weight="600" style={{ display: 'block', marginBottom: '8px' }}>
									✅ Stärken
								</Text>
								<ul style={{ margin: 0, paddingLeft: '20px', fontSize: '13px' }}>
									{analysis.strengths.map((s, i) => (
										<li key={i} style={{ marginBottom: '4px' }}>{s}</li>
									))}
								</ul>
							</div>
						)}

						{/* Improvements */}
						{analysis.improvements?.length > 0 && (
							<div style={{ marginBottom: '15px' }}>
								<Text weight="600" style={{ display: 'block', marginBottom: '8px' }}>
									💡 {strings.suggestions}
								</Text>
								{analysis.improvements.map((imp, i) => (
									<Card key={i} size="small" style={{ marginBottom: '8px' }}>
										<CardBody style={{ padding: '10px' }}>
											<Flex justify="space-between" align="start">
												<FlexItem>
													<Text size="13px">{imp.suggestion}</Text>
												</FlexItem>
												<FlexItem>
													<PriorityBadge priority={imp.priority} />
												</FlexItem>
											</Flex>
										</CardBody>
									</Card>
								))}
							</div>
						)}

						{/* Missing Sections */}
						{analysis.missingSections?.length > 0 && (
							<div style={{ marginBottom: '15px' }}>
								<Text weight="600" style={{ display: 'block', marginBottom: '8px' }}>
									📦 {strings.missingSections}
								</Text>
								{analysis.missingSections.map((section, i) => (
									<Card key={i} size="small" style={{ marginBottom: '8px' }}>
										<CardBody style={{ padding: '10px' }}>
											<Text weight="600" size="13px">{section.name}</Text>
											<Text size="12px" variant="muted">{section.description}</Text>
											<Button
												variant="secondary"
												size="small"
												onClick={() => {
													setSelectedSection(section.type);
													generateSection();
												}}
												style={{ marginTop: '8px' }}
											>
												<Icon icon={plus} size={16} /> Generieren
											</Button>
										</CardBody>
									</Card>
								))}
							</div>
						)}

						{/* Keywords */}
						{analysis.keywords && (
							<div>
								<Text weight="600" style={{ display: 'block', marginBottom: '8px' }}>
									🔑 Keywords
								</Text>
								{analysis.keywords.found?.length > 0 && (
									<p style={{ fontSize: '12px', margin: '0 0 5px' }}>
										<strong>Gefunden:</strong> {analysis.keywords.found.join(', ')}
									</p>
								)}
								{analysis.keywords.suggested?.length > 0 && (
									<p style={{ fontSize: '12px', margin: 0 }}>
										<strong>Empfohlen:</strong> {analysis.keywords.suggested.join(', ')}
									</p>
								)}
							</div>
						)}
					</div>
				)}
			</PanelBody>

			{/* Generate Section Panel */}
			<PanelBody title={strings.generateSection} initialOpen={false}>
				<SelectControl
					value={selectedSection}
					options={sectionOptions}
					onChange={setSelectedSection}
				/>
				<Button
					variant="secondary"
					onClick={generateSection}
					disabled={!selectedSection || isGenerating}
					style={{ width: '100%', justifyContent: 'center' }}
				>
					{isGenerating ? (
						<Fragment>
							<Spinner /> {strings.generating}
						</Fragment>
					) : (
						<Fragment>
							<Icon icon={plus} style={{ marginRight: '8px' }} />
							{strings.generateSection}
						</Fragment>
					)}
				</Button>
			</PanelBody>

			{/* Generated Blocks Modal */}
			{showGenerateModal && (
				<Modal
					title="Generierte Section"
					onRequestClose={() => setShowGenerateModal(false)}
				>
					<div style={{ marginBottom: '15px' }}>
						<Text>Die Section wurde generiert. Möchtest du sie einfügen?</Text>
					</div>
					<TextareaControl
						value={generatedBlocks}
						onChange={setGeneratedBlocks}
						rows={10}
						style={{ fontFamily: 'monospace', fontSize: '11px' }}
					/>
					<Flex justify="flex-end" gap={2} style={{ marginTop: '15px' }}>
						<Button variant="tertiary" onClick={() => setShowGenerateModal(false)}>
							{strings.discard}
						</Button>
						<Button variant="primary" onClick={applyGeneratedBlocks}>
							{strings.apply}
						</Button>
					</Flex>
				</Modal>
			)}
		</Fragment>
	);
};

/**
 * Register the plugin
 */
registerPlugin('ramboeck-page-optimizer', {
	render: () => (
		<Fragment>
			<PluginSidebarMoreMenuItem target="ramboeck-page-optimizer">
				KI-Optimierer
			</PluginSidebarMoreMenuItem>
			<PluginSidebar
				name="ramboeck-page-optimizer"
				title="KI-Optimierer"
				icon={sparkles}
			>
				<PageOptimizer />
			</PluginSidebar>
		</Fragment>
	),
});
