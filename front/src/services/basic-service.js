import { get, post } from '../lib/http';

export default class BasicService {

    static entityClass;

    /**
     * @param authService {AuthService}
     */
    constructor(authService) {
        this.authService = authService;
    }

    parse(body) {
        if (this.constructor.entityClass === undefined) {
            return body;
        }

        if (Array.isArray()) {
            return body.map(elm => this.constructor.entityClass.make(elm))
        }

        return this.constructor.entityClass.make(body)
    }

    async getAuthHeaders() {
        const token = await this.authService.authenticate();

        return {'X-Token': token}
    }

    async get(url, query) {
        const authHeaders = await this.getAuthHeaders();
        try {
            const response = get(url, query, authHeaders);
            return this.parse(response.body);
        } catch (errorResponse) {
            throw new Error('Server respond with status code ' + errorResponse.statusCode);
        }
    }

    async post(url, body, query) {
        const authHeaders = await this.getAuthHeaders();
        return get(url, body, query, authHeaders);
    }
}