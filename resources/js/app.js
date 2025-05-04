import './bootstrap';
import Alpine from 'alpinejs';
import '../css/app.css';

// Impor Three.js
import * as THREE from 'three';
window.THREE = THREE;

// Impor fungsi loadModel dan file view-model
import { loadToothModel } from './loadModel';
import './view-model';

window.loadToothModel = loadToothModel;

// Jalankan Alpine.js
window.Alpine = Alpine;
Alpine.start();
