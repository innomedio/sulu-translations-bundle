import React, {useState} from 'react';
import AIConfig, {buildTranslateRoute} from "sulu-translations-bundle/ai/config";
import FeatureBadge from "sulu-admin-bundle/containers/AiApplication/FeatureBadge";
import Translator from "sulu-admin-bundle/containers/Translator";
import {translate} from "sulu-admin-bundle/utils";

/**
 * @param {Object} props
 * @param {String} props.value
 * @param {String} props.locale
 * @param {String} props.translationKey
 * @param {Function} props.onTranslationConfirm
 * @returns {React.Element|null}
 * @constructor
 */
function TranslateWithAi({
    value,
    locale,
    translationKey,
    onTranslationConfirm
}) {
    const [translating, setTranslating] = useState(false);
    const finishTranslating = (text) => {
        onTranslationConfirm(text);
        setTranslating(false);
    }

    if (!AIConfig.enabled) {
        return null;
    }

    return (
        <div>
            <FeatureBadge
                messages={{
                    translate: translate('sulu_admin.translator'),
                    writingAssistant: translate('sulu_admin.writing_assistant'),
                }}
                onTranslateClick={() => setTranslating(true)}
                onWritingAssistantClick={undefined}
                skin={'gray'}
            />
            {translating && (
                <Translator
                    locale={locale}
                    messages={{
                        title: translate('sulu_admin.translator'),
                        insert: translate('sulu_admin.insert'),
                        detected: translate('sulu_admin.detected'),
                        errorTranslatingText: translate('sulu_admin.translator_error'),
                    }}
                    onConfirm={finishTranslating}
                    onDialogClose={() => setTranslating(false)}
                    sourceLanguages={AIConfig.sourceLanguages}
                    targetLanguages={AIConfig.targetLanguages}
                    type={'text_area'}
                    url={buildTranslateRoute()}
                    value={value || translationKey}
                />
            )}
        </div>
    );
}

export default TranslateWithAi;
