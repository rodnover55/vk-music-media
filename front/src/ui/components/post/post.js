import React from 'react'

import di from '../../../di'

import Tag from '../tag/tag';

export default class Post extends React.Component {

    /** @member PlayerService */
    playerService;

    constructor(...args) {
        super(...args);
        //noinspection JSUnresolvedVariable
        this.playerService = di.container.PlayerService;
    }

    onPlayClick(id) {
        this.playerService.playPost(id)
    }

    render() {
        return (
            <article className="post">
                <img className="postImage" src={this.props.image} alt=""/>
                <div className="postDescription __collapsed">{this.props.description}</div>
                <div className="postTags">
                    {this.props.tags.map((tag, index) => <Tag key={index} tag={tag} />)}
                </div>
                <button onClick={() => this.onPlayClick(this.props.id)} className="postPlay">
                    Воспроизвести пост
                </button>
            </article>
        )
    }
}