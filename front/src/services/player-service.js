const CALLBACKS = Symbol('CALLBACKS');
const CURRENT_POST = Symbol('CURRENT_POST');

export default class PlayerService {

    /** @member PostService */
    postService;

    /** @member PostEntity */
    [CURRENT_POST];

    constructor(postService) {
        this.postService = postService;
        this[CALLBACKS] = new Set;
        this[CURRENT_POST] = null;
    }

    removeCallback(callback) {
        this[CALLBACKS].delete(callback);
    }

    setCallback(callback) {
        this[CALLBACKS].add(callback);
    }

    async playPost(id) {
        const post = await this.postService.getById(id);
        this[CURRENT_POST] = post;

        for (const cb of this[CALLBACKS]) {
            cb(post);
        }
    }
}