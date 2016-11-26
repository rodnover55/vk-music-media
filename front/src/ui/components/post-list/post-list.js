import React from 'react'

import Post from '../post/post'

export default class PostList extends React.Component {

    get posts() {
        return [];
    }

    render() {
        return (
            <div className="postList">
                {this.posts.map(post => <Post {...post} />)}
            </div>
        )
    }
}