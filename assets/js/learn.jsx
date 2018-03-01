let React = require('react');
let ReactDOM = require('react-dom');

class Rate extends React.Component{
    rate(rating){
        fetch('/react/learn/rate/' + this.props.itemId, {
            method: 'POST',
            body: JSON.stringify({
                learn_rating: rating,
            }),
            headers: {
                'Authorization': this.props.pw
            },
        }).then(res => {
            location.reload();
        })
            .then(
                (result) => {
       //
                },
                // Note: it's important to handle errors here
                // instead of a catch() block so that we don't swallow
                // exceptions from actual bugs in components.
                (error) => {
    //
                }
            )
    };

    render(){
        let buttons = [];

        for (let i = 0; i < 6; i++) {
            buttons.push(
                <button onClick={() => this.rate(i)} key={i} type="submit" className="btn btn-light btn-lg" name="learn_rating" value={i}>{i}</button>
            )
        }

        return (
            <div>{buttons}</div>
        )
    }
}

class Card extends React.Component{
    render(){
        return <div className="row">
            <div className="card card-outline-secondary mb-3">
                <div className="card-body">
                    <p>{this.props.content}</p>
                </div>
            </div>
        </div>;
    }
}

class Learn extends React.Component {

    constructor() {
        super();
        this.state = {
            showResults: false
        };
    }

    toggleShowAnswer() {
        this.setState((prevState, props) => {
            return { showResults: !prevState.showResults }
        });
    };

    render() {

        return (
            <div id="learn-view">
                <Card content={this.props.item.question}/>
                <Rate itemId={this.props.item.id} pw={this.props.pw}/>
                <button onClick={() => this.toggleShowAnswer()} className="btn btn-light js-show btn-lg btn-block">
                    <span className="oi oi-elevator"></span>
                </button>
                { this.state.showResults ? <Card content={this.props.item.answer}/> : null }
            </div>
        )
    }
}

ReactDOM.render(<Learn item={GoGo.item}  pw={GoGo.basicAuthValue}/>, document.getElementById("learn-jsx"));