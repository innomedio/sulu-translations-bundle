import {listToolbarActionRegistry} from 'sulu-admin-bundle/views';
import {listFieldTransformerRegistry} from 'sulu-admin-bundle/containers';
import {initializer} from 'sulu-admin-bundle/services';
import ExportTranslationsToolbarAction from "./components/ExportTranslationsToolbarAction";
import InlineEditFieldTransformer from "./components/InlineEditFieldTransformer";
import {enableTranslatorAi} from "./ai/config";

initializer.addUpdateConfigHook('sulu_admin', (config, initialized) => {
    if (!initialized) {
        registerToolbarActions();
        registerFieldTransformers();
    }
});

initializer.addUpdateConfigHook('sulu_ai', (config, initialized) => {
    if (initialized || undefined === config) {
        return;
    }

    if (!config.translation || !config.translation.enabled) {
        return;
    }

    enableTranslatorAi(config.translation);
});

function registerToolbarActions() {
    listToolbarActionRegistry.add('tailr_translation.export_translations', ExportTranslationsToolbarAction);
}

function registerFieldTransformers() {
    listFieldTransformerRegistry.add('tailr_translation.inline_edit', new InlineEditFieldTransformer());
}
