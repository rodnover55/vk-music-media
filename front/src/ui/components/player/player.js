import React from 'react'

import di from '../../../di';

import Track from '../track/track';
import ReactPlayer from 'react-player';

export default class Player extends React.Component {

    state = {
        post: null,
        currentTrack: null,
        hidePlaylist: true,
        played: 0,
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

    async playTrack(currentTrack) {
        const url = await this.playerService.getMediaUrl(currentTrack);

        this.setState({
            currentTrack,
            playerOptions: {
                ...this.state.playerOptions,
                ...{playing: true, url: url}
            }
        });
    }

    /**
     * @param post {PostEntity}
     */
    playPost(post) {
        this.setState({post});
        this.playerService.playTrack(post.tracks[0])
    }

    currentTrack() {
        if (this.state.currentTrack === null) {
            return <div className="playerPlayListTrackTitle">Плейлист пуст</div>;
        }

        return (
            <div className="playerPlayListTrackTitle">
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
                <h4 className="playerPlayListTitle">Текущий плейлист: {this.state.post.title}</h4>
                <ul className="playerPlayListTracks">
                    {this.state.post.tracks.map((track, index) => { return (
                        <Track key={index} track={track} currentTrack={this.state.currentTrack} />
                    )})}
                </ul>
            </div>
        )
    }

    onProgress(played) {
        this.setState({played: played * 100})
    }

    player() {
        if (this.state.currentTrack === null) {
            return '';
        }

        return <ReactPlayer onProgress={({played})=>this.onProgress(played)} {...this.state.playerOptions} />
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
                <div className="playerControls">
                    <button
                        disabled={this.state.currentTrack === null}
                        onClick={()=>this.playPrev()}
                        className="playerButton __prev">
                    </button>
                    <button
                        disabled={this.state.currentTrack === null}
                        onClick={()=>this.playPause()}
                        className={this.state.playerOptions.playing ? 'playerButton __pause' : 'playerButton __play'}>
                    </button>
                    <button
                        disabled={this.state.currentTrack === null}
                        onClick={()=>this.playNext()}
                        className="playerButton __next">
                    </button>
                </div>
                <div className="playerProgress">
                    {this.currentTrack()}
                    <div className="playerProgressBar">
                        <div style={{width: this.state.played + '%'}} className="playerProgressHandle"></div>
                    </div>
                </div>
                <div className="playerSound"></div>
                <button
                    disabled={this.state.currentTrack === null}
                    onClick={()=>this.togglePlaylist()}
                    className="playerButton __showList">
                </button>
                {this.playlist()}
            </div>
        )
    }
}