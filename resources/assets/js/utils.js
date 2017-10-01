import _ from  'lodash';

export const getIcon = (key) => {
    return window.AppData.icons[key] || null;
};

export const apiRoute = (name, params) => {
    let route = window.AppData.routes[name] || null;

    if (! route) {
        throw Error(`API Route not found for ${name}`);
    }

    _.each(params, (value, key) => {
        route = route.replace(`:${key}`, value);
    });

    return route;
};
