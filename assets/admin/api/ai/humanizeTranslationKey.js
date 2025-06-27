import {Requester} from "sulu-admin-bundle/services";
import {runActionOnServer} from "../../utilities/run-action-on-server";
import AIConfig, {buildWritingAssistantRoute} from "../../ai/writing-assistant";

/**
 * @param {string} translationKey - The translation key to humanize.
 * @param {string} locale - The locale for which the translation key should be humanized.
 *
 * @returns {Promise<string>}
 */
export async function humanizeTranslationKey(translationKey, locale){
    const selectedExpert = Object.values(AIConfig.experts)[0]?.uuid ?? '';

    if (!AIConfig.enabled || !selectedExpert) {
        throw new Error('AI WritingAssistant is not enabled');
    }

    const HTTPResponse = await runActionOnServer(
        Requester.post(buildWritingAssistantRoute(), {
            text: translationKey,
            message: 'The user considers the provided input to be a translation key. You do your best understanding the context and humanize it to a more readable format.',
            expertUuid: selectedExpert,
            locale,
        })
    );

    return HTTPResponse.response.text;
}
