@extends('layouts.app')

@section('head')
<script src="https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/controls/OrbitControls.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/GLTFLoader.js"></script>

<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="max-w-3xl mx-auto px-4 py-4">
    <div class="min-h-screen flex flex-col items-center py-8">
        <h1 class="text-2xl font-bold mb-4">Patient Input Form</h1>

        <form id="patientForm" method="POST" action="{{ route('patients.store') }}" class="w-full max-w-3xl bg-white p-6 rounded shadow">
            @csrf
            <input type="hidden" name="odontogram_data" id="odontogram_data">

            <!-- General Information -->
            <section class="mb-6">
                <h2 class="text-xl font-semibold mb-4">General Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium">Patient Name *</label>
                        <input type="text" id="name" name="name" required class="mt-1 block w-full p-2 border rounded" placeholder="Enter patient name" />
                    </div>
                    <div>
                        <label for="age" class="block text-sm font-medium">Age</label>
                        <input type="number" id="age" name="age" class="mt-1 block w-full p-2 border rounded" placeholder="Enter patient age" />
                    </div>
                    <div>
                        <label for="gender" class="block text-sm font-medium">Gender</label>
                        <select id="gender" name="gender" class="mt-1 block w-full p-2 border rounded">
                            <option value="">Select gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div>
                        <label for="contact" class="block text-sm font-medium">Contact Number *</label>
                        <input type="text" id="contact" name="contact" required class="mt-1 block w-full p-2 border rounded" placeholder="Enter contact number" />
                    </div>
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium">Address</label>
                        <textarea id="address" name="address" rows="2" class="mt-1 block w-full p-2 border rounded" placeholder="Enter patient address"></textarea>
                    </div>
                </div>
            </section>

            <!-- Medical Information -->
            <section class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Medical Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="blood_type" class="block text-sm font-medium">Blood Type</label>
                        <select id="blood_type" name="blood_type" class="mt-1 block w-full p-2 border rounded">
                            <option value="">Select blood type</option>
                            @foreach($bloodTypes as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="blood_pressure" class="block text-sm font-medium">Blood Pressure</label>
                        <input type="text" id="blood_pressure" name="blood_pressure" class="mt-1 block w-full p-2 border rounded" placeholder="e.g. 120/80" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium">Medical History</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mt-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" id="heart_disease" name="heart_disease" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2">Heart Disease</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" id="diabetes" name="diabetes" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2">Diabetes</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" id="hepatitis" name="hepatitis" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2">Hepatitis</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label for="allergies" class="block text-sm font-medium">Allergies</label>
                        <textarea id="allergies" name="allergies" rows="2" class="mt-1 block w-full p-2 border rounded" placeholder="List any allergies"></textarea>
                    </div>
                    <div>
                        <label for="blood_disorders" class="block text-sm font-medium">Blood Disorders</label>
                        <textarea id="blood_disorders" name="blood_disorders" rows="2" class="mt-1 block w-full p-2 border rounded" placeholder="List any blood disorders"></textarea>
                    </div>
                    <div>
                        <label for="other_diseases" class="block text-sm font-medium">Other Diseases</label>
                        <textarea id="other_diseases" name="other_diseases" rows="2" class="mt-1 block w-full p-2 border rounded" placeholder="List any other diseases"></textarea>
                    </div>
                    <div>
                        <label for="current_medication" class="block text-sm font-medium">Current Medication</label>
                        <textarea id="current_medication" name="current_medication" rows="2" class="mt-1 block w-full p-2 border rounded" placeholder="List current medications"></textarea>
                    </div>
                </div>
            </section>

            <!-- Odontogram -->
            <div class="odontogram-container mb-4">
                <h2 class="text-xl font-semibold mb-4">Odontogram</h2>

                <div class="odontogram">
                  <div class="jaw upper-jaw">
                    <div class="tooth" data-number="18">18</div>
                    <div class="tooth" data-number="17">17</div>
                    <div class="tooth" data-number="16">16</div>
                    <div class="tooth" data-number="15">15</div>
                    <div class="tooth" data-number="14">14</div>
                    <div class="tooth" data-number="13">13</div>
                    <div class="tooth" data-number="12">12</div>
                    <div class="tooth" data-number="11">11</div>
                    <div class="tooth" data-number="21">21</div>
                    <div class="tooth" data-number="22">22</div>
                    <div class="tooth" data-number="23">23</div>
                    <div class="tooth" data-number="24">24</div>
                    <div class="tooth" data-number="25">25</div>
                    <div class="tooth" data-number="26">26</div>
                    <div class="tooth" data-number="27">27</div>
                    <div class="tooth" data-number="28">28</div>
                </div>
        
                <!-- Baris Gigi Tengah Atas -->
                <div class="jaw middle-upper-jaw">
                    <div class="tooth" data-number="55">55</div>
                    <div class="tooth" data-number="54">54</div>
                    <div class="tooth" data-number="53">53</div>
                    <div class="tooth" data-number="52">52</div>
                    <div class="tooth" data-number="51">51</div>
                    <div class="tooth" data-number="61">61</div>
                    <div class="tooth" data-number="62">62</div>
                    <div class="tooth" data-number="63">63</div>
                    <div class="tooth" data-number="64">64</div>
                    <div class="tooth" data-number="65">65</div>
                </div>
        
                <!-- Baris Gigi Tengah Bawah -->
                <div class="jaw middle-lower-jaw">
                    <div class="tooth" data-number="85">85</div>
                    <div class="tooth" data-number="84">84</div>
                    <div class="tooth" data-number="83">83</div>
                    <div class="tooth" data-number="82">82</div>
                    <div class="tooth" data-number="81">81</div>
                    <div class="tooth" data-number="71">71</div>
                    <div class="tooth" data-number="72">72</div>
                    <div class="tooth" data-number="73">73</div>
                    <div class="tooth" data-number="74">74</div>
                    <div class="tooth" data-number="75">75</div>
                </div>
        
                <!-- Baris Gigi Bawah -->
                <div class="jaw lower-jaw">
                    <div class="tooth" data-number="48">48</div>
                    <div class="tooth" data-number="47">47</div>
                    <div class="tooth" data-number="46">46</div>
                    <div class="tooth" data-number="45">45</div>
                    <div class="tooth" data-number="44">44</div>
                    <div class="tooth" data-number="43">43</div>
                    <div class="tooth" data-number="42">42</div>
                    <div class="tooth" data-number="41">41</div>
                    <div class="tooth" data-number="31">31</div>
                    <div class="tooth" data-number="32">32</div>
                    <div class="tooth" data-number="33">33</div>
                    <div class="tooth" data-number="34">34</div>
                    <div class="tooth" data-number="35">35</div>
                    <div class="tooth" data-number="36">36</div>
                    <div class="tooth" data-number="37">37</div>
                    <div class="tooth" data-number="38">38</div>
                </div>
            </div>
                </div>

                <div class="tooth-details bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="font-medium text-lg mb-3">Detail Gigi: <span id="selected-tooth" class="font-bold"></span></h3>
                        <div class="form-group">
                            <label for="condition">Kondisi:</label>
                            <select id="condition" name="condition">
                                <option value="healthy">Sehat</option>
                                <option value="caries">Karies</option>
                                <option value="filling">Tambalan</option>
                                <option value="extracted">Dicabut</option>
                                <option value="root_canal">Perawatan Saluran Akar</option>
                                <option value="crown">Mahkota</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="surface">Permukaan:</label>
                            <select id="surface" name="surface">
                                <option value="whole">Seluruh Gigi</option>
                                <option value="buccal">Buccal</option>
                                <option value="lingual">Lingual</option>
                                <option value="occlusal">Occlusal</option>
                                <option value="mesial">Mesial</option>
                                <option value="distal">Distal</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="notes">Catatan:</label>
                            <textarea id="notes" name="notes"></textarea>
                        </div>
                        <button id="saveOdontogram" type="button">Simpan Data Gigi</button>
                </div>
            </section>

            <!-- Dental Conditions -->
            <section class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Dental Conditions</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="occlusion" class="block text-sm font-medium">Occlusion</label>
                        <input type="text" id="occlusion" name="occlusion" class="mt-1 block w-full p-2 border rounded" placeholder="Enter occlusion" />
                    </div>
                    <div>
                        <label for="torus_palatinus" class="block text-sm font-medium">Torus Palatinus</label>
                        <input type="text" id="torus_palatinus" name="torus_palatinus" class="mt-1 block w-full p-2 border rounded" placeholder="Enter torus palatinus" />
                    </div>
                    <div>
                        <label for="torus_mandibularis" class="block text-sm font-medium">Torus Mandibularis</label>
                        <input type="text" id="torus_mandibularis" name="torus_mandibularis" class="mt-1 block w-full p-2 border rounded" placeholder="Enter torus mandibularis" />
                    </div>
                    <div>
                        <label for="supernumerary" class="block text-sm font-medium">Supernumerary Teeth</label>
                        <input type="text" id="supernumerary" name="supernumerary" class="mt-1 block w-full p-2 border rounded" placeholder="Enter supernumerary teeth" />
                    </div>
                    <div>
                        <label for="diastema" class="block text-sm font-medium">Diastema</label>
                        <input type="text" id="diastema" name="diastema" class="mt-1 block w-full p-2 border rounded" placeholder="Enter diastema" />
                    </div>
                    <div>
                        <label for="other_anomalies" class="block text-sm font-medium">Other Anomalies</label>
                        <input type="text" id="other_anomalies" name="other_anomalies" class="mt-1 block w-full p-2 border rounded" placeholder="Enter any other anomalies" />
                    </div>
                </div>
            </section>

            <!-- Doctor's Notes -->
            <section class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Doctor's Notes</h2>
                <textarea id="doctor_notes" name="doctor_notes" rows="4" class="mt-1 block w-full p-2 border rounded" placeholder="Enter any additional notes here"></textarea>
            </section>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Submit Patient Data
                </button>
                <button id="show3dBtn" type="button" style="display:none">Show 3D</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .tooth {
        display: inline-block;
        width: 30px;
        height: 30px;
        margin: 2px;
        text-align: center;
        line-height: 30px;
        border: 1px solid #ccc;
        border-radius: 3px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .tooth:hover {
        background-color: #f0f0f0;
    }
    
    .tooth.active {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
    }
    
    /* Tooth condition styles */
    .healthy { background-color: #d1fae5; }
    .caries { background-color: #fecaca; }
    .filling { background-color: #bfdbfe; }
    .extracted { background-color: #e5e7eb; text-decoration: line-through; }
    .root_canal { background-color: #ddd6fe; }
    .crown { background-color: #fef08a; }
    
    .jaw {
        margin-bottom: 10px;
        text-align: center;
    }
    
    .upper-jaw, .middle-upper-jaw {
        margin-bottom: 5px;
    }
    
    .lower-jaw, .middle-lower-jaw {
        margin-top: 5px;
    }
    
    /* Toast styles */
    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 16px;
        border-radius: 4px;
        color: white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        opacity: 1;
        transition: opacity 0.3s ease;
    }
    
    .toast.info { background-color: #3b82f6; }
    .toast.success { background-color: #10b981; }
    .toast.error { background-color: #ef4444; }
    .toast.warning { background-color: #f59e0b; }
</style>
@endpush

@php
    $bloodTypes = $bloodTypes ?? [
        'A' => 'A',
        'B' => 'B',
        'AB' => 'AB',
        'O' => 'O'
    ];
@endphp

@vite(['resources/css/app.css', 'resources/js/odontogram.js'])