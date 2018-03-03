//let React = require('react');
//let ReactDOM = require('react-dom');


export class Notifications extends React.Component{

    render(){
        return(
            <div>
            { this.props.message ? <div className="alert alert-success">{this.props.message}</div> : null}
            </div>
        )
    }
}
