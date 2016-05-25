import {Component} from 'angular2/core'

@Component({
    selector: 'author',
    template: `
    <h2>Course Authors!</h2>
    {{ title }}
    `
})
export class AuthorComponent {
    title = "The title of Authors page";
    courses = []
}