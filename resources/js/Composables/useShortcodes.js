export const useShortcodes = () => {
    /**
     * Parse attribute string into object.
     * Duplicate logic of PHP for client-side processing if needed.
     */
    const parseAttributes = (attrString) => {
        const attributes = {};
        const pattern = /(\w+)(?:=(?:"([^"]*)"|'([^']*)'|(\S+)))?/g;
        let match;

        while ((match = pattern.exec(attrString)) !== null) {
            const key = match[1];
            let value = match[2] || match[3] || match[4] || true;

            if (typeof value === 'string') {
                if (!isNaN(value) && !isNaN(parseFloat(value))) value = Number(value);
                else if (value === 'true') value = true;
                else if (value === 'false') value = false;
            }
            attributes[key] = value;
        }

        return attributes;
    };

    return {
        parseAttributes
    };
};
