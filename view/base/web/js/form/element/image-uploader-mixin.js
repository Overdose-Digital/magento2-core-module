define(function () {
    'use strict';

    var mixin = {

        /**
         * Add SVG extension type to allowed extensions.
         * @returns {string}
         */
        getAllowedFileExtensionsInCommaDelimitedFormat: function () {
            let result = this._super();
            result = result.split(',');
            if (Array.isArray(result)) {
                result.push('SVG');
            }
            return result.join(', ');
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});
