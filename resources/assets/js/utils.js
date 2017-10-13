import _ from  'lodash';

export const getIcon = (key) => {
    return window.icons[key] || null;
};
