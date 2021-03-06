import React from 'react'

import di from '../../../di';

export default class Track extends React.Component {

    /** @member PlayerService */
    playerService;

    constructor(...args) {
        super(...args);
        //noinspection JSUnresolvedVariable
        this.playerService = di.container.PlayerService;
    }

    get className() {
        let className ="track";

        if (this.props.track === this.props.currentTrack) {
            className += ' __active';
        }

        return className;
    }

    onPlayClick(track) {
        this.playerService.playTrack(track)
    }

    render() {
        return (
            <li className={this.className}>
                {this.props.track.artist} &mdash; {this.props.track.title}
                <button onClick={()=>this.onPlayClick(this.props.track)} className="trackPlay"></button>
            </li>
        )
    }
}