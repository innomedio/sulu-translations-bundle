import symfonyRouting from "fos-jsrouting/router";

/**
 * @typedef {Object} Config
 * @property {boolean} enabled Whether the AI translator is enabled
 * @property {string} route The route to the AI translation service
 * @property {Object} Experts A list of source languages supported by the AI translator
 *
 * @type {Config}
 */
var config = {
    enabled: false,
    route: '',
    experts: {},
};

export const buildWritingAssistantRoute = () => config.enabled ? symfonyRouting.generate(config.route, {
    chatId: Math.floor(Math.random() * 10000000),
}) : '';
export const enableWritingAssistantAi = (newConfig) => Object.assign(config, newConfig);

export default config;
