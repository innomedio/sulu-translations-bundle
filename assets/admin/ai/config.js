import symfonyRouting from "fos-jsrouting/router";

/**
 * @typedef {Object} Config
 * @property {boolean} enabled Whether the AI translator is enabled
 * @property {string} route The route to the AI translation service
 * @property {Array} sourceLanguages A list of source languages supported by the AI translator
 * @property {Array} targetLanguages A list of target languages supported by the AI translator
 *
 * @type {Config}
 */
var config = {
    enabled: false,
    route: '',
    sourceLanguages: [],
    targetLanguages: [],
};

export const buildTranslateRoute = () => config ? symfonyRouting.generate(config.route, {
    translateId: Math.floor(Math.random() * 10000000),
}) : '';
export const enableTranslatorAi = (newConfig) => Object.assign(config, newConfig);

export default config;
