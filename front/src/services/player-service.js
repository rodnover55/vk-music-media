const POST_CALLBACKS = Symbol('CALLBACKS');
const TRACK_CALLBACKS = Symbol('TRACK_CALLBACKS');
const CURRENT_POST = Symbol('CURRENT_POST');

export default class PlayerService {

    /** @member PostService */
    postService;

    /** @member PostEntity */
    [CURRENT_POST];

    constructor(postService) {
        this.postService = postService;
        this[POST_CALLBACKS] = new Set;
        this[TRACK_CALLBACKS] = new Set;
        this[CURRENT_POST] = null;
    }

    removePostCallback(callback) {
        this[POST_CALLBACKS].delete(callback);
    }

    setPostCallback(callback) {
        this[POST_CALLBACKS].add(callback);
    }

    removeTrackCallback(callback) {
        this[TRACK_CALLBACKS].delete(callback);
    }

    setTrackCallback(callback) {
        this[TRACK_CALLBACKS].add(callback);
    }

    async playPost(id) {
        const post = await this.postService.getById(id);
        this[CURRENT_POST] = post;

        for (const cb of this[POST_CALLBACKS]) {
            cb(post);
        }
    }

    playTrack(id) {
        const track = this[CURRENT_POST].tracks.find(track => track.id === id);

        if (track === undefined) {
            throw new Error('Current post does not contain this track');
        }

        for (const cb of this[TRACK_CALLBACKS]) {
            cb(track);
        }
    }
}