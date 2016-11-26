import React from 'react'
import { IndexLink } from 'react-router'

export default class NotFound extends React.Component {

    render() {
        return (
            <section className="page">
                <h1 className="pageHeader">Страница не найдена</h1>
                <IndexLink className="pageLink" to="/">На главную</IndexLink>
            </section>
        )
    }
}