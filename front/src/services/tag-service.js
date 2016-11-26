import BasicService from './basic-service';

const URL = '/api/tags';

export default class TagService extends BasicService {

    getTags() {
        return this.get(URL);
    }
}