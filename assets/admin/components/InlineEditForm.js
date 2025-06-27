import React, {useState} from "react";
import {updateTranslationValue} from "../api/updateTranslationValue";
import {translate} from "sulu-admin-bundle/utils";
import snackbarStore  from "sulu-admin-bundle/stores/snackbarStore";
import TranslateWithAi  from "./ai/TranslateWithAi";

const styles = {
    container: {
        display: 'flex',
        alignItems: 'center',
        gap: '5px',
    },
    textarea: {
        resize: 'none',
    },
};

/**
 *
 * @param {Object} props
 * @param {Number} props.translationId
 * @param {String} props.value
 * @param {String} props.locale
 * @param {String} props.translationKey
 * @returns {React.Element}
 * @constructor
 */
function InlineEditForm({translationId, value, locale, translationKey}) {
    const [saving, setSaving] = useState(false);
    const [editingValue, setEditingValue] = useState(value || '');

    const onError = () => {
        snackbarStore.add(
            {type: 'error', text: translate('tailr_translations.update_general_error_message')},
            8000
        );
        setEditingValue(value);
    }

    const saveValue = (newValue) => {
        setEditingValue(newValue);
        if (value === newValue) {
            return;
        }

        setSaving(true);
        updateTranslationValue(translationId, newValue)
            .catch(onError)
            .finally(() => setSaving(false));
    };

    const onChange = (event) => setEditingValue(event.target.value);
    const onBlur = (event) => {
        saveValue(event.target.value);
    }

    return (
        <div style={styles.container}>
            <textarea
                key={`tailr_translations.inline_edit_form_${translationId}`}
                style={styles.textarea}
                rows={2}
                cols={45}
                disabled={saving}
                value={editingValue}
                onChange={onChange}
                onBlur={onBlur}
            >
                {editingValue}
            </textarea>
            <TranslateWithAi
                defaultValue={editingValue}
                onTranslationConfirm={saveValue}
                locale={locale}
                translationKey={translationKey}
            />
        </div>
    );
}

export default InlineEditForm;
