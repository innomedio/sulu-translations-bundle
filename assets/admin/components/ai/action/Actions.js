import React from 'react';
import ConvertTranslationKey from "./ConvertTranslationKey";

const styles = {
    container: {
        display: 'flex',
        margin: '0 50px',
        marginTop: '30px',
    },
};

/**
 * @param {Object} props
 * @param {String} props.value
 * @param {String} props.locale
 * @param {String} props.translationKey
 * @param {Function} props.onChange
 * @returns {React.Element}
 * @constructor
 */
function Actions(props) {
    return (
        <div style={styles.container}>
            <ConvertTranslationKey {...props} />
        </div>
    );
}

export default Actions;
