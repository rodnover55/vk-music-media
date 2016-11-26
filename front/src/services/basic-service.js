import { get, post } from '../lib/http';
import Entity from '../lib/entity';

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

        if (Array.isArray(body)) {
            return body.map(elm => Entity.make(this.constructor.entityClass, elm))
        }

        return Entity.make(this.constructor.entityClass, body)
    }

    async getAuthHeaders() {
        const token = await this.authService.authenticate();

        return {'X-Token': token}
    }

    async get(url, query) {
        const authHeaders = await this.getAuthHeaders();
        try {
            const response = await get(url, query, authHeaders);
            return this.parse(response.body);
        } catch (errorResponse) {
            if (errorResponse.statusCode !== undefined) {
                throw new Error('Server respond with status code ' + errorResponse.statusCode);
            }

            throw errorResponse;
        }
    }

    async post(url, body, query) {
        const authHeaders = await this.getAuthHeaders();
        return await post(url, body, query, authHeaders);
    }
}