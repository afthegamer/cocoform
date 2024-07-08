import { useBlockProps } from '@wordpress/block-editor';

const Save = ({ attributes }) => {
	const blockProps = useBlockProps.save();
	const { formData } = attributes;
	const { fields = [] } = formData || {};
	const emailField = fields.find(field => field.label === 'Email') || {};
	const subjectField = fields.find(field => field.label === 'Objet') || {};

	return (
		<div {...blockProps}>
			<style>{`
                .cocoform-wrapper {
                    margin: 0 auto;
                    padding: 20px;
                    background: #f5f5f5;
                    border-radius: 8px;
                    max-width: 600px;
                }
                .cocoform-field {
                    margin-bottom: 15px;
                }
                .cocoform-label {
                    display: block;
                    margin-bottom: 5px;
                    font-weight: bold;
                }
                .cocoform-input,
                .cocoform-select {
                    width: 100%;
                    padding: 10px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    box-sizing: border-box;
                }
                .cocoform-input:focus,
                .cocoform-select:focus {
                    border-color: #0073aa;
                    box-shadow: 0 0 5px rgba(0, 115, 170, 0.5);
                    outline: none;
                }
                @media (max-width: 600px) {
                    .cocoform-wrapper {
                        padding: 10px;
                    }
                    .cocoform-input,
                    .cocoform-select {
                        font-size: 14px;
                        padding: 8px;
                    }
                }
                @media (max-width: 400px) {
                    .cocoform-input,
                    .cocoform-select {
                        font-size: 12px;
                        padding: 6px;
                    }
                }
            `}</style>

			<div className="cocoform-wrapper">
				<div className="cocoform-field">
					<label className="cocoform-label">{emailField.label}</label>
					<input type="email" className="cocoform-input" value={emailField.default}/>
				</div>
				<div className="cocoform-field">
					<label className="cocoform-label">{subjectField.label}</label>
					{subjectField.type === 'text' ? (
						<input type="text" className="cocoform-input" value={subjectField.default}/>
					) : (
						<select className="cocoform-select">
							{subjectField.options && subjectField.options.map((option, index) => (
								<option key={index} value={option}>{option}</option>
							))}
						</select>
					)}
				</div>
				{fields.length > 0 && fields.filter(field => field.label !== 'Email' && field.label !== 'Objet').map((field, index) => (
					<div className="cocoform-field" key={index}>
						<label className="cocoform-label">{field.label}</label>
						<input type={field.type} className="cocoform-input" readOnly/>
					</div>
				))}
			</div>
		</div>
	);
};

export default Save;
