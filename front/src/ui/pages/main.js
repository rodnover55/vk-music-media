import React from 'react'
import { Link } from 'react-router'

import PostList from '../components/post-list/post-list';

export default class NotFound extends React.Component {

    render() {
        return (
            <section className="page">
                <PostList/>
            </section>
        )
    }
}