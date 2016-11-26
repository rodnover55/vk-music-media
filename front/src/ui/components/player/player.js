import React from 'react'

import di from '../../../di';

import Track from '../track/track';

export default class Player extends React.Component {

    state = {
        post: null,
        currentTrack: null,
        hidePlaylist: true
    };

    /** @member PlayerService */
    playerService;

    constructor(...args) {
        super(...args);
        //noinspection JSUnresolvedVariable
        this.playerService = di.container.PlayerService;
        this.playPost = this.playPost.bind(this);
        this.playTrack = this.playTrack.bind(this);
    }

    componentDidMount() {
        this.playerService.setPostCallback(this.playPost);
        this.playerService.setTrackCallback(this.playTrack);
    }

    componentWillUnmount() {
        this.playerService.removePostCallback(this.playPost);
        this.playerService.removeTrackCallback(this.playTrack);
    }

    playTrack(currentTrack) {
        this.setState({currentTrack});
    }

    /**
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

    currentTrack() {
        if (this.state.currentTrack === null) {
            return '';
        }

        return (
            <div>
                Сейчас играет:
                {this.state.currentTrack.artist} &mdash; {this.state.currentTrack.title}
            </div>
        );
    }

    playlist() {
        if (this.state.post === null) {
            return '';
        }
        return (
            <div hidden={this.state.hidePlaylist} className="playerPlayList">
                {this.state.post.tracks.map((track, index) => { return (
                    <Track key={index} track={track} currentTrack={this.state.currentTrack} />
                )})}
            </div>
        )
    }

    togglePlaylist() {
        this.setState({hidePlaylist: !this.state.hidePlaylist});
    }

    render() {
        return (
            <div className="player">
                {this.currentPlaylist()}
                <button className="playerButton __prev">&larr;</button>
                <button className="playerButton __play">Play</button>
                <button className="playerButton __next">&rarr;</button>
                {this.currentTrack()}
                <div className="playerProgress"></div>
                <div className="playerSound"></div>
                <button onClick={()=>this.togglePlaylist()} className="playerShowPlaylist">
                    show playlist
                </button>
                {this.playlist()}
            </div>
        )
    }
}