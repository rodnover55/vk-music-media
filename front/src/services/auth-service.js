import { post } from '../lib/http';

const URL = '/api/token';

function extractAppParams () {
    const queryString = window.location.search.substr(1);

    if (queryString === '') {
        return {};
    }

    const pairs = window.location.search.substr(1).split('&');
    const result = {};
    for(const item of pairs) {
        const [key, value] = item.split('=');
        if (value === '') {
            result[decodeURIComponent(key)] = true;
        } else {
            result[decodeURIComponent(key)] = decodeURIComponent(value);
        }
    }

    return result;
}

const TOKEN = Symbol('TOKEN');

export default class AuthService {

    get token() {
        return this[TOKEN];
    }

    async authenticate() {
        if (this[TOKEN] !== undefined) {
            return this[TOKEN];
        }

        const response = await post(URL, extractAppParams());
        this[TOKEN] = response.token;
        return response.token;
    }
}