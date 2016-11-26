import React from 'react'

import di from '../../../di';

import Track from '../track/track';
import ReactPlayer from 'react-player';

export default class Player extends React.Component {

    state = {
        post: null,
        currentTrack: null,
        hidePlaylist: true,
        playerOptions: {
            playing: false,
            volume: 0.8,
            hidden: true
        }
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
        this.setState({
            currentTrack,
            playerOptions: {...this.state.playerOptions, ...{playing: true}}
        });
    }

    /**
     * @param post {PostEntity}
     */
    playPost(post) {
        this.setState({post});
        this.playerService.playTrack(post.tracks[0])
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

    player() {
        if (this.state.currentTrack === null) {
            return '';
        }

        return <ReactPlayer url={this.state.currentTrack.link} {...this.state.playerOptions} />
    }

    togglePlaylist() {
        this.setState({hidePlaylist: !this.state.hidePlaylist});
    }

    playPause() {
        if (this.state.post !== null) {
            if (this.state.currentTrack === null) {
                this.playerService.playTrack(this.state.post.tracks[0]);
            } else {
                this.setState({playerOptions: {
                    ...this.state.playerOptions,
                    ...{playing: !this.state.playerOptions.playing}
                }})
            }
        }
    }

    playNext() {
        if (this.state.post !== null) {
            const track = this.state.currentTrack || this.state.post.tracks[0];
            this.playerService.playNext(track)
        }
    }

    playPrev() {
        if (this.state.post !== null) {
            const track = this.state.currentTrack || this.state.post.tracks[0];
            this.playerService.playPrev(track)
        }
    }

    render() {
        return (
            <div className="player">
                {this.player()}
                {this.currentPlaylist()}
                <button
                    disabled={this.state.currentTrack === null}
                    onClick={()=>this.playPrev()}
                    className="playerButton __prev">
                    &larr;
                </button>
                <button
                    disabled={this.state.currentTrack === null}
                    onClick={()=>this.playPause()}
                    className="playerButton __play">
                    Play
                </button>
                <button
                    disabled={this.state.currentTrack === null}
                    onClick={()=>this.playNext()}
                    className="playerButton __next">
                    &rarr;
                </button>
                {this.currentTrack()}
                <div className="playerProgress"></div>
                <div className="playerSound"></div>
                <button
                    disabled={this.state.currentTrack === null}
                    onClick={()=>this.togglePlaylist()}
                    className="playerShowPlaylist">
                    show playlist
                </button>
                {this.playlist()}
            </div>
        )
    }
}