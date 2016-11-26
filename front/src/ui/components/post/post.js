import React from 'react'

import di from '../../../di'

import Tag from '../tag/tag';

export default class Post extends React.Component {

    state = {
        collapsed: true
    };

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

    expand() {
        if (this.state.collapsed) {
            this.setState({collapsed: false})
        }
    }

    render() {
        return (
            <article className={this.state.collapsed ? 'post __collapsed' : 'post'}>
                <div
                    className="postImage"
                    style={{backgroundImage: 'url(' + this.props.image + ')'}}></div>
                <h4 className="postTitle">{this.props.title}</h4>
                <div
                    onClick={()=>this.expand()}
                    className={this.state.collapsed ? 'postDescription __collapsed' : 'postDescription'}
                    dangerouslySetInnerHTML={{__html: this.props.description}}>
                </div>
                <div className="postTags">
                    {this.props.tags.map((tag, index) => <Tag key={index} tag={tag} />)}
                </div>
                <button onClick={() => this.onPlayClick(this.props.id)} className="postPlay">
                </button>
            </article>
        )
    }
}