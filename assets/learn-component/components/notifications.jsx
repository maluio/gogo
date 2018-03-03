//let React = require('react');
//let ReactDOM = require('react-dom');


export class Notifications extends React.Component{

    render(){
        return(
            <div className="col-5">
            { this.props.message ? <div className="alert alert-info">{this.props.message}</div> : null}
            </div>
        )
    }
}
