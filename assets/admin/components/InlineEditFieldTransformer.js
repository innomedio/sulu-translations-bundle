import React from 'react';
import InlineEditForm from "./InlineEditForm";

export default class InlineEditFieldTransformer {

    /**
     * @param {string} _value
     * @param {null} _
     * @param {Object} row
     * @param {string} row.translation
     * @param {number} row.id
     * @param {string} row.locale
     * @param {string} row.translationKey
     * @returns {React.Element}
     */
    transform(_value, _, row) {
        return (
            <InlineEditForm
                value={row.translation}
                translationId={row.id}
                locale={row.locale}
                translationKey={row.translationKey}
            />
        );
    }
}
