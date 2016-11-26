import BasicService from './basic-service';

const URL = '/api/posts';

export default class PostService extends BasicService {

    getRecentPosts() {
        return this.get(URL);
    }
}