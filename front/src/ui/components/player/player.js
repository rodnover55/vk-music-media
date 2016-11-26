import React from 'react'

export default class Player extends React.Component {

    render() {
        return (
            <div className="player">
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