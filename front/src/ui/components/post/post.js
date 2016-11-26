import React from 'react'

import Tag from '../tag/tag';

export default class Post extends React.Component {

    render() {
        return (
            <article className="post">
                <img className="postImage" src={this.props.image} alt=""/>
                <div className="postDescription __collapsed">{this.props.description}</div>
                <div className="postTags">
                    {this.props.tags.map((tag, index) => <Tag key={index} tag={tag} />)}
                </div>
            </article>
        )
    }
}