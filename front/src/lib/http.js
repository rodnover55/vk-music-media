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
        url += '?' + queryString
    }

    return new Promise((resolve, reject) => {
        ajax({
            method: 'GET',
            url,
            headers
        }, (statusCode, body) => {
            body = body || 'null';

            try {
                body = JSON.parse(body);
            } catch (e) {
                body = {message: body}
            }

            if (statusCode >= 400 || statusCode === 0) {
                reject({statusCode, body})
            } else {
                resolve({statusCode, body})
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
        }, (statusCode, body) => {
            body = body || null;

            try {
                body = JSON.parse(body);
            } catch (e) {
                body = {message: body}
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