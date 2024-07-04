import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import './editor.scss';

const Save = () => {
	const blockProps = useBlockProps.save();

	return (
		<div {...blockProps}>
			<div className="cocoform-wrapper">
				<InnerBlocks.Content />
			</div>
		</div>
	);
};

export default Save;
