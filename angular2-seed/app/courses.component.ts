import {Component} from 'angular2/core'

@Component({
    selector: 'courses',
    template: `
    <h2>Cool Courses!</h2>
    {{ title }}
    `
})
export class CoursesComponent {
    title = "The title of courses page";
}