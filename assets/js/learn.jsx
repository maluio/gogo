let React = require('react');
let ReactDOM = require('react-dom');

class Learn extends React.Component {
    constructor(props) {
        super(props);
        console.log(props);
    }

    render(){
        return (<h1>hi</h1>);
    }
}

ReactDOM.render(<Learn />, document.getElementById("learn-jsx"));

console.log(GoGo.item.question);
