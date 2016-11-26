import React from 'react'

import di from '../../../di';

export default class Player extends React.Component {

    state = {
        post: null,
        currentTrack: null
    };

    /** @member PlayerService */
    playerService;

    constructor(...args) {
        super(...args);
        //noinspection JSUnresolvedVariable
        this.playerService = di.container.PlayerService;
        this.playPost = this.playPost.bind(this);
    }

    componentDidMount() {
        this.playerService.setCallback(this.playPost);
    }

    componentWillUnmount() {
        this.playerService.removeCallback(this.playPost)
    }

    /**
     *
     * @param post {PostEntity}
     */
    playPost(post) {
        this.setState({post});
    }

    currentPlaylist() {
        if (this.state.post === null) {
            return '';
        }

        return (
            <div>Текущий плейлист: {this.state.post.title}</div>
        );
    }

    render() {
        return (
            <div className="player">
                {this.currentPlaylist()}
                <button className="playerButton __prev">&larr;</button>
                <button className="playerButton __play">Play</button>
                <button className="playerButton __next">&rarr;</button>
                <div className="playerProgress"></div>
                <div className="playerSound"></div>
                <button className="playerPlaylist">show playlist</button>
            </div>
        )
    }
}