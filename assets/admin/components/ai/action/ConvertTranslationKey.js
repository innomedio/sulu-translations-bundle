import React, {useState} from 'react';
import {Button} from "sulu-admin-bundle/components";
import {translate} from "sulu-admin-bundle/utils";
import AIConfig from "../../../ai/writing-assistant";
import {humanizeTranslationKey} from "../../../api/ai/humanizeTranslationKey";
import snackbarStore  from "sulu-admin-bundle/stores/snackbarStore";
/**
 * @param {Object} props
 * @param {String} props.locale
 * @param {String} props.translationKey
 * @param {Function} props.onChange
 * @returns {React.Element}
 * @constructor
 */
function ConvertTranslationKey({locale, translationKey, onChange}) {

    const [loading, setLoading] = useState(false);
    const onClick =() => {
        setLoading(true);
        humanizeTranslationKey(translationKey, locale)
            .then((response) => onChange(response))
            .catch(
                (error) => {
                    console.error('Error humanizing translation key:', error);
                    snackbarStore.add(
                        {type: 'error', text: translate('tailr_translations.unable_to_humanize_translation_key')},
                        8000
                    );
                }
            )
            .finally(() => setLoading(false));
    };

    if (!AIConfig.enabled) {
        return null;
    }

    return (
        <Button
            onClick={onClick}
            loading={loading}
        >
            {translate('tailr_translations.ai_action.humanize')}
        </Button>
    );
}

export default ConvertTranslationKey;
