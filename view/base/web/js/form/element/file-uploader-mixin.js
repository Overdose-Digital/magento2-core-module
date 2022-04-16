define(function () {
    'use strict';

    var mixin = {

        /**
         * Add svg extension type to allowed extensions.
         * @param file
         * @returns {*}
         */
        isFileAllowed: function (file) {
            let allowedExtensions = this.allowedExtensions;
            allowedExtensions = allowedExtensions.split(' ');
            if (Array.isArray(allowedExtensions)) {
                allowedExtensions.push('svg');
            }
            this.allowedExtensions = allowedExtensions.join(' ');
            return this._super(file);
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});
