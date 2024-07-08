import { useBlockProps, BlockControls } from '@wordpress/block-editor';
import { ToolbarGroup, ToolbarButton } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';

const Edit = ({ attributes, setAttributes, clientId, isSelected }) => {
	const blockProps = useBlockProps();
	const [formData, setFormData] = useState(null);

	useEffect(() => {
		fetch('/wp-json/cocoform/v1/form/brad')
			.then(response => response.json())
			.then(data => {
				setFormData(data);
				setAttributes({ formData: data });
			})
			.catch(error => console.error('Erreur:', error));
	}, []);

	if (!formData) {
		return <div {...blockProps}>Chargement du formulaire...</div>;
	}

	return (
		<div {...blockProps} className="cocoform-wrapper">
			{isSelected && (
				<BlockControls>
					<ToolbarGroup>
						<ToolbarButton
							icon="trash"
							label="Supprimer le bloc"
							onClick={() => wp.data.dispatch('core/block-editor').removeBlock(clientId)}
						/>
						<ToolbarButton
							icon="move"
							label="Déplacer le bloc"
							onClick={() => wp.data.dispatch('core/block-editor').moveBlock(clientId, /*fromIndex, toIndex*/)}
						/>
					</ToolbarGroup>
				</BlockControls>
			)}
			<form className="cocoform-form">
				{formData.fields.map((field, index) => (
					<div className="cocoform-field" key={index}>
						<label className="cocoform-label">{field.label}</label>
						{field.type === 'text' && <input type="text" className="cocoform-input" />}
						{field.type === 'email' && <input type="email" className="cocoform-input" />}
						{field.type === 'number' && <input type="number" className="cocoform-input" />}
						{field.type === 'textarea' && <textarea className="cocoform-textarea" />}
						{field.type === 'select' && (
							<select className="cocoform-select">
								{field.options.map((option, idx) => (
									<option key={idx} value={option}>{option}</option>
								))}
							</select>
						)}
					</div>
				))}
			</form>
		</div>
	);
};

export default Edit;
