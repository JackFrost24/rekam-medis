import './bootstrap';

import Alpine from 'alpinejs';
import '../css/app.css'; 
import { loadToothModel } from './loadModel';



window.loadToothModel = loadToothModel;


window.Alpine = Alpine;

Alpine.start();
