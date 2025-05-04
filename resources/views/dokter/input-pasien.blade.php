@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-4">
    <div class="min-h-screen flex flex-col items-center py-8">
        <h1 class="text-2xl font-bold mb-4">Patient Input Form</h1>

        <form id="patientForm" method="POST" action="{{ route('patients.store') }}" class="w-full max-w-3xl bg-white p-6 rounded shadow">
          <input type="hidden" name="odontogram_data" id="odontogram_data">
            <!-- General Information -->
            <section class="mb-6">
                <h2 class="text-xl font-semibold mb-4">General Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium">Patient Name</label>
                        <input type="text" id="name" name="name" class="mt-1 block w-full p-2 border rounded" placeholder="Enter patient name" />
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
                        <label for="contact" class="block text-sm font-medium">Contact Number</label>
                        <input type="text" id="contact" name="contact" class="mt-1 block w-full p-2 border rounded" placeholder="Enter contact number" />
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
                    <form id="tooth-form">
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
                        <button type="submit">Simpan</button>
                </div>
            </section>

            <!-- Dental Conditions -->
            <section class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Dental Conditions</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Input lainnya -->
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
            <section>
                <h2 class="text-xl font-semibold mb-4">Doctor's Notes</h2>
                <textarea id="doctor_notes" name="doctor_notes" rows="4" class="mt-1 block w-full p-2 border rounded" placeholder="Enter any additional notes here"></textarea>
            </section>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@vite(['resources/css/odontogram.css', 'resources/js/odontogram.js'])
