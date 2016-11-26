import { post } from '../lib/http';

const POST_CALLBACKS = Symbol('CALLBACKS');
const TRACK_CALLBACKS = Symbol('TRACK_CALLBACKS');
const CURRENT_POST = Symbol('CURRENT_POST');

const VK_URL = 'https://api.vk.com/method/audio.getById';

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

    /**
     * @param track {TrackEntity}
     */
    async getMediaUrl(track) {
        const response = await post(VK_URL, {
            audios: `${track.owner_id}_${track.aid}`,
            version: '5.60'
        });

        return response.body.url;
    }

    playTrack(desireTrack) {
        const track = this[CURRENT_POST].tracks.find(track => track === desireTrack);

        if (track === undefined) {
            throw new Error('Current post does not contain this track');
        }

        for (const cb of this[TRACK_CALLBACKS]) {
            cb(track);
        }
    }

    playNext(currentTrack) {
        let index = this[CURRENT_POST].tracks.indexOf(currentTrack) + 1;

        if (this[CURRENT_POST].tracks[index] === undefined) {
            index = 0;
        }

        for (const cb of this[TRACK_CALLBACKS]) {
            cb(this[CURRENT_POST].tracks[index]);
        }
    }

    playPrev(currentTrack) {
        let index = this[CURRENT_POST].tracks.indexOf(currentTrack) - 1;

        if (this[CURRENT_POST].tracks[index] === undefined) {
            index = this[CURRENT_POST].tracks.length - 1;
        }

        for (const cb of this[TRACK_CALLBACKS]) {
            cb(this[CURRENT_POST].tracks[index]);
        }
    }
}