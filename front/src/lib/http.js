import {ajax} from 'nanoajax';

const defaultHeaders = {
    'Content-Type': 'application/json'
};

function buildQueryString(obj = {}) {
    const result = [];
    for (const [key, value] of Object.entries(obj)) {
        result.push(encodeURIComponent(key) + '=' + encodeURIComponent(value))
    }

    return result.join('&')
}

async function get(url, params = {}, headers = {}) {
    headers = {...defaultHeaders, ...headers};
    const queryString = buildQueryString(params);

    if (queryString !== '') {
        url += queryString
    }

    return new Promise((resolve, reject) => {
        ajax({
            method: 'GET',
            url,
            headers
        }, (statusCode, body) => {

            try {
                body = JSON.parse(body);
            } catch (e) {
                throw new Error('Response body is not a valid JSON');
            }

            if (statusCode >= 400 || statusCode === 0) {
                reject({statusCode, body: JSON.parse(body)})
            } else {
                resolve({statusCode, body: JSON.parse(body)})
            }
        })
    });
}

async function post(url, body = {}, params = {}, headers = {}) {
    headers = {...defaultHeaders, ...headers};
    const queryString = buildQueryString(params);

    if (queryString !== '') {
        url += queryString
    }

    return new Promise((resolve, reject) => {
        ajax({
            method: 'POST',
            url,
            body: JSON.stringify(body),
            headers
        }, (statusCode, body = '') => {

            try {
                body = JSON.parse(body);
            } catch (e) {
                throw new Error('Response body is not a valid JSON');
            }

            if (statusCode >= 400 || statusCode === 0) {
                reject({statusCode, body})
            } else {
                resolve({statusCode, body})
            }
        })
    });
}

export { get, post}