import BasicService from './basic-service';
import PostEntity from '../entities/post-entity';

const URL = '/api/posts';

export default class PostService extends BasicService {

    static entityClass = PostEntity;

    getRecentPosts() {
        return this.get(URL);
    }

    async refreshPosts() {
        const response = await this.post(URL + '-refresh');

        if (response.statusCode !== 204) {
            throw new Error('Invalid response status code ' + response.statusCode);
        }

        return true;
    }
}